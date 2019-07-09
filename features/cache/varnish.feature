@varnish
Feature: As an site administrator I want my pages to be cached using Varnish

#  @admin
#  Scenario Outline: Content Items are cached for Anonymous when visited
#    Given I create "Folder" Content items in root in "eng-GB"
#      | name       | short_name |
#      | TestFolder | <itemName> |
#    And I viewing the pages on siteaccess "site" as <user>
#    When I visit <itemName> on siteaccess "site"
#    And I reload the page
#    Then I see correct preview data for "Folder" Content Type
  #    | field | value      |
 #     | title | <itemName> |
 #   And response headers contain
#      | Header        | Value                 |
#      | cache-control | <cache-control-value> |
#      | X-Cache       | HIT                                          |
#      | X-Cache-Hits  | 1                                            |
#    And "Age" response header has value greater than 0
#
#    Examples:
#    | user      | itemName                     | cache-control-value                                                  |
#    | admin     | TestFolderShortNameAdmin     | private, no-cache, no-store, must-revalidate                         |
#    | anonymous | TestFolderShortNameAnonymous | public, s-maxage=600, stale-while-revalidate=300, stale-if-error=300 |

  @admin
  Scenario Outline: Cache is refreshed when item is edited
    Given I create "Folder" Content items in root in "eng-GB"
      | name       | short_name |
      | TestFolder | <itemName> |
    And I viewing the pages on siteaccess "site" as <user>
    And I visit "<itemName>" on siteaccess "site"
    And I see correct preview data for "Folder" Content Type
      | field | value      |
      | title | <itemName> |
    When I edit "<itemName>" Content item in "eng-GB"
      | short_name          |
      | <itemNameAfterEdit> |
    And I reload the page
    And I reload the page
    And I reload the page
    And I reload the page
    And I reload the page
    Then I see correct preview data for "Folder" Content Type
      | field | value               |
      | title | <itemNameAfterEdit> |
    And response headers contain
      | Header        | Value                                        |
      | cache-control | public, s-maxage=600, stale-while-revalidate=300, stale-if-error=300 |
#      | X-Cache       | HIT                                          |
#      | X-Cache-Hits  | 1                                            |

    Examples:
      | user      | itemName        | itemNameAfterEdit |
      | admin     | NameToEditAdmin | NameEditedAdmin   |
      | anonymous | NameToEditAnon  | NameEditedAnon    |
