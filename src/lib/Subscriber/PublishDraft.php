<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Behat\Subscriber;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\UserService;
use EzSystems\Behat\API\ContentData\RandomDataGenerator;
use EzSystems\Behat\API\Facade\WorkflowFacade;
use EzSystems\Behat\Event\Events;
use EzSystems\Behat\Event\TransitionEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PublishDraft extends AbstractProcessStage implements EventSubscriberInterface
{
    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \EzSystems\Behat\API\Facade\WorkflowFacade */
    private $workflowFacade;

    protected function getTransitions(): array
    {
        return [
            Events::PUBLISH_TO_END => 0.8,
            Events::PUBLISH_TO_EDIT => 0.2,
        ];
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::REVIEW_TO_PUBLISH => 'execute',
        ];
    }

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                UserService $userService,
                                PermissionResolver $permissionResolver,
                                LoggerInterface $logger,
                                ContentService $contentService,
                                WorkflowFacade $workflowFacade,
                                RandomDataGenerator $randomDataGenerator)
    {
        parent::__construct($eventDispatcher, $userService, $permissionResolver, $logger, $randomDataGenerator);
        $this->contentService = $contentService;
        $this->randomDataGenerator = $randomDataGenerator;
        $this->workflowFacade = $workflowFacade;
    }

    protected function doExecute(TransitionEvent $event): void
    {
        $transitionName = 'done';
        $this->workflowFacade->transition($event->content, $transitionName, $this->randomDataGenerator->getRandomTextLine());
        $event->content = $this->contentService->publishVersion($event->content->versionInfo);
    }
}
