@symfonycache
Feature: As an site administrator I want my pages to be cached using Symfony Http Cache

  @admin
  Scenario Outline: Content Items are cached for Anonymous when visited
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name |
      | TestFolder | <itemName> |
    And I viewing the pages on siteaccess "site" as <user>
    When I visit "TestFolderShortName" on siteaccess "site"
    Then I see correct preview data for "Folder" Content Type
      | field | value      |
      | title | <itemName> |
    And response headers contain
      | Header        | Value             |
      | Cache-Control | no-cache, private |
    And "Age" response header has value greater than 0

    Examples:
    | user      | itemName                     |
    | admin     | TestFolderShortNameAdmin     |
    | anonymous | TestFolderShortNameAnonymous |

  @admin
  Scenario Outline: Cache is refreshed when item is edited
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name       |
      | TestFolder | <itemName><user> |
    And I viewing the pages on siteaccess "site" as <user>
    And I visit "TestFolderShortNameToEdit" on siteaccess "site"
    And I see correct preview data for "Folder" Content Type
      | field | value                     |
      | title | <itemName><user> |
    When I edit "TestFolderShortNameToEdit" Content item in "eng-GB"
      | short_name           |
      | <itemName>Edit<user> |
    And I reload the page
    Then I see correct preview data for "Folder" Content Type
      | field | value                |
      | title | <itemName>Edit<user> |
    And response headers contain
      | Header        | Value             |
      | Cache-Control | no-cache, private |

    Examples:
      | user      | itemName                  |
      | admin     | TestFolderShortNameToEdit |
      | anonymous | TestFolderShortNameToEdit |