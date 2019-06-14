@varnish
Feature: Example scenarios showing how to use steps involving Roles and Users

  @admin
  Scenario: Create a Folder and make sure it's cached
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name          |
      | TestFolder | TestFolderShortName |
    And I visit "TestFolderShortName" on siteaccess "site"
    And I see correct preview data for "Folder" Content Type
     | field | value      |
     | title | TestFolderShortName |
    And response headers contain
     | Header    | Value |
     | Cache-Control | public, s-maxage=86400 |
    When I reload the page
    Then I see correct preview data for "Folder" Content Type
      | field | value      |
      | title | TestFolderShortName |
    And response headers contain
      | Header    | Value |
      | Cache-Control | public, s-maxage=86400 |

  @admin @cache
  Scenario: Edit a Folder and make sure the cache is refreshed
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name          |
      | TestFolder | TestFolderShortNameToEdit |
    And I visit "TestFolderShortNameToEdit" on siteaccess "site"
    And I reload the page
    And I see correct preview data for "Folder" Content Type
      | field | value      |
      | title | TestFolderShortNameToEdit |
    And response headers contain
      | Header    | Value |
      | Cache-Control | public, s-maxage=86400 |
    When I edit "TestFolderShortNameToEdit" Content item in "eng-GB"
      | short_name             |
      | TestFolderEdited |
    And I reload the page
    Then I see correct preview data for "Folder" Content Type
      | field | value      |
      | title | TestFolderEdited |
    And response headers contain
      | Header    | Value |
      | Cache-Control | public, s-maxage=86400 |