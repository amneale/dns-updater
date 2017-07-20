Feature: Update DNS
  In order to update DNS records
  As an owner of a domain
  I need to be able to update A records for that domain to point to a given IP

  Scenario: Updating a domain A records
    Given there is an A record "@" pointing at "127.0.0.1"
    And my IP resolves to "123.45.67.89"
    When I update DNS records
    Then the domain A record "@" should point to "123.45.67.89"
