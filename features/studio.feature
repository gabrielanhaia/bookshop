Feature: Tests related to managing studios

  @db
  Scenario: Open a new studio
    Given I send a POST request to "/studios" with body:
    """
    {
        "name": "Colesseo Studio",
        "street": "Via Colosseo 2",
        "city": "Rome",
        "zipCode": "54321",
        "country": "IT",
        "email": "contact@colesseiostudio.com"
    }
    """
    Then the response status code should be 201
    And the response should contain JSON:
    """
    {
        "id": "@uuid@",
        "name": "Colesseo Studio",
        "street": "Via Colosseo 2",
        "city": "Rome",
        "zipCode": "54321",
        "country": "IT",
        "email": "contact@colesseiostudio.com"
    }
    """

  Scenario: Tries to open a new studio with invalid email address
    Given I send a POST request to "/studios" with body:
    """
    {
        "name": "Colesseo Studio",
        "street": "Via Colosseo 2",
        "city": "Rome",
        "zipCode": "54321",
        "country": "IT",
        "email": "invalid-email"
    }
    """
    Then the response status code should be 400
