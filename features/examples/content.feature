Feature: Example scenarios showing how to use steps involving Languages, Content Types and Content Items

  @admin
  Scenario Outline: Create a language, Content Type and Content Items
  Given Language "Polski" with code "pol-PL" exists
  And I create a "<contentTypeName>" Content Type in "Content" with "<contentTypeIdentifier>" identifier
  | Field Type  | Name         | Identifier        | Required | Searchable | Translatable |
  | Text line   | Name         | name	           | yes      | yes	       | yes          |
  | <fieldType> | TestedField  | testedfield       | yes      | no	       | yes          |
  And I create "Folder" Content items in root in "pol-PL"
    | name              | short_name          |
    | <contentTypeName> | <contentTypeName>   |
  And I create 50 "<contentTypeIdentifier>" Content items in "<contentTypeName>" in "pol-PL"

    Examples:
    | contentTypeName      | contentTypeIdentifier | fieldType                    |
    | RichText CT          | RichTextCT            | Rich text                    |
    | URL CT               | URLCT                 | URL                          |
    | Email CT             | EmailCT               | Email address                |
    | Textline CT          | TextlineCT            | Text line                    |
    | ISBN CT              | IsbnCT                | ISBN                         |
    | Authors CT           | AuthorsCT             | Authors                      |
    | Text block CT        | TextBlockCT           | Text block                   |
    | Checkbox CT          | CheckboxCT            | Checkbox                     |
    | Country CT           | CountryCT             | Country                      |
    | Date CT              | DateCT                | Date                         |
    | Time CT              | TimeCT                | Time                         |
    | Float CT             | FloatCT               | Float                        |
    | Integer CT           | Integer               | Integer                      |
    | Map location CT      | MapLocationCT         | Map location                 |
    | Date and time CT     | DateAndTimeCT         | Date and time                |
    | Content relation CT  | ContentRelationCT     | Content relation (single)    |
    | Content relations CT | ContentRelationsCT    | Content relations (multiple) |
    | Image CT             | ImageCT               | Image                        |
    | File CT              | FileCT                | File                         |
    | Media CT             | MediaCT               | Media                        |

  @admin
  Scenario Outline: Create a Content item and edit specified field
    Given I create <contentTypeIdentifier> Content items in root in "eng-GB"
      | name                    | short_name              |
      | <contentTypeIdentifier> | <contentTypeIdentifier> |
    And I create "Folder" Content items in root in "eng-GB"
      | name              | short_name      |
      | RelationFolder1   | RelationFolder1 |
      | RelationFolder2   | RelationFolder2 |
    When I edit "<contentTypeIdentifier>" Content item in "eng-GB"
      | testedfield  |
      | <valueToSet> |

  Examples:
    | contentTypeIdentifier | valueToSet                                                                  |
    | RichTextCT            | EditedField                                                                 |
    | URLCT                 | www.ez.no                                                                   |
    | EmailCT               | nospam@ez.no                                                                |
    | TextlineCT            | TestTextLine                                                                |
    | IsbnCT                | 9783161484100                                                               |
    | AuthorsCT             | AuthorName,nospam@ez.no                                                     |
    | TextBlockCT           | TestTextBlock                                                               |
    | CheckboxCT            | true                                                                        |
    | CountryCT             | FR                                                                          |
    | DateCT                | 2018-12-31                                                                  |
    | TimeCT                | 13:55                                                                       |
    | FloatCT               | 2.34                                                                        |
    | Integer               | 10                                                                          |
    | MapLocationCT         | 59.19930,9.61360                                                            |
    | DateAndTimeCT         | 2018-12-31 13:55                                                            |
    | ContentRelationCT     | /RelationFolder1                                                            |
    | ContentRelationsCT    | RelationFolder1,/RelationFolder2                                            |
    | ImageCT               | /var/www/ezplatform/vendor/ezsystems/behatbundle/lib/Data/Images/small1.jpg |
    | FileCT                | /var/www/ezplatform/vendor/ezsystems/behatbundle/lib/Data/Files/file1.txt   |
    | MediaCT               | /var/www/ezplatform/vendor/ezsystems/behatbundle/lib/Data/Videos/video1.mp4 |
