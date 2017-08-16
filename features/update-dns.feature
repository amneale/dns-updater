Feature: Update DNS
  In order to update DNS records
  As an owner of a domain
  I need to be able to update A records for that domain to point to a given IP

  Scenario: Creating a domain record
    Given there are no existing domain records
    When I update the A record "my" for domain "test.domain" with the value "123.45.67.89"
    Then there should exist the A record "my" for domain "test.domain" with the value "123.45.67.89"

  Scenario: Updating a domain record
    Given there is the A record "my" for domain "test.domain" with the value "123.45.67.89"
    When I update the A record "my" for domain "test.domain" with the value "111.11.11.11"
    Then there should exist the A record "my" for domain "test.domain" with the value "111.11.11.11"

  Scenario: Automatically resolving host IP
    Given there is the A record "my" for domain "test.domain" with the value "123.45.67.89"
    And my IP resolves as "111.11.11.11"
    When I update the A record "my" for domain "test.domain"
    Then there should exist the A record "my" for domain "test.domain" with the value "111.11.11.11"
