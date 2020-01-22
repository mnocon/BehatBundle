<?php


namespace EzSystems\BehatBundle\API\ContentData\FieldTypeData;


use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\FieldType\ImageAsset\AssetMapper;
use eZ\Publish\Core\FieldType\ImageAsset\Value;
use EzSystems\BehatBundle\API\ContentData\RandomDataGenerator;
use EzSystems\BehatBundle\Helper\ArgumentParser;

class ImageAssetDataProvider extends AbstractFieldTypeDataProvider
{
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @var AssetMapper
     */
    private $assetMapper;
    /**
     * @var ImageDataProvider
     */
    private $imageDataProvider;

    private $mappings;

    public function __construct(RandomDataGenerator $randomDataGenerator, SearchService $searchService, AssetMapper $assetMapper, ImageDataProvider $imageDataProvider, $mappings)
    {
        parent::__construct($randomDataGenerator);
        $this->searchService = $searchService;
        $this->assetMapper = $assetMapper;
        $this->imageDataProvider = $imageDataProvider;
        $this->mappings = $mappings;
    }

    public function supports(string $fieldTypeIdentifier): bool
    {
        return $fieldTypeIdentifier === 'ezimageasset';
    }

    public function generateData(string $contentTypeIdentifier, string $fieldIdentifier, string $language = 'eng-GB')
    {
        $this->setLanguage($language);

        $imageAssetContentTypeIdentifier = $this->mappings['content_type_identifier'];
        $imageAssetFieldIdentifier = $this->assetMapper->getContentFieldIdentifier();

        $imageAssetName = $this->getFaker()->sentence(2);
        $imageValue = $this->imageDataProvider->generateData($imageAssetContentTypeIdentifier, $imageAssetFieldIdentifier, $language);

        $content = $this->assetMapper->createAsset($imageAssetName, $imageValue, $language);

        $altText = $this->getFaker()->sentence;

        return new Value($content->getVersionInfo()->getContentInfo()->id, $altText);
    }

    public function parseFromString(string $value)
    {
        // todo
    }

    protected function getContentID(string $locationPath)
    {
        $locationURL = $this->argumentParser->parseUrl($locationPath);
        $urlAlias = $this->urlAliasService->lookup($locationURL);

        $location = $this->locationService->loadLocation($urlAlias->destination);

        return $location->getContentInfo()->id;
    }
}