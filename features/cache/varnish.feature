@symfonycache
Feature: As an site administrator I want my pages to be cached using Varnish

  @admin
  Scenario outline: Content Items are cached for Anonymous when visited
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name                   |
      | TestFolder | TestFolderShortNameAnonymous |
    And I am not logged in siteaccess "site"
    When I visit "TestFolderShortName" on siteaccess "site"
    And I reload the page
    Then I see correct preview data for "Folder" Content Type
      | field | value               |
      | title | TestFolderShortName |
    And response headers contain
      | Header        | Value                                        |
      | Cache-Control | private, no-cache, no-store, must-revalidate |
      | X-Cache       | HIT                                          |
      | X-Cache-Hits  | 1                                            |
    And "Age" response header has value greater than 0

    Examples:
     | user      |
     | admin     |
     | anonymous |

  @admin
  Scenario: Content Items are cached for Admin when visited
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name               |
      | TestFolder | TestFolderShortNameAdmin |
    And I am logged as "admin" on siteaccess "site"
    When I visit "TestFolderShortName" on siteaccess "site"
    Then I see correct preview data for "Folder" Content Type
      | field | value                    |
      | title | TestFolderShortNameAdmin |
    And response headers contain
      | Header        | Value             |
      | Cache-Control | no-cache, private |
    And "Age" response header has value greater than 0

  @admin
  Scenario: Cache is refreshed when item is edited
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name                |
      | TestFolder | TestFolderShortNameToEdit |
    And I am not logged in siteaccess "site"
    And I visit "TestFolderShortNameToEdit" on siteaccess "site"
    And I see correct preview data for "Folder" Content Type
      | field | value                     |
      | title | TestFolderShortNameToEdit |
    When I edit "TestFolderShortNameToEdit" Content item in "eng-GB"
      | short_name       |
      | TestFolderEdited |
    And I reload the page
    And I reload the page
    Then I see correct preview data for "Folder" Content Type
      | field | value            |
      | title | TestFolderEdited |
    And response headers contain
      | Header        | Value             |
      | Cache-Control | no-cache, private |