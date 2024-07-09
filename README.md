# Dancing Studios

![image](https://github.com/gabrielanhaia/studio-renting/assets/15172908/585d9c50-2ceb-4c10-aede-12150bab4607)


## How to Start and Test the Project

### Requirements
- Docker

### Steps

1. Clone the project.
2. Run `make setup` in the terminal inside the project folder. This will copy the necessary files, install dependencies, and build a Docker container.

This setup process will:
- Copy configuration files:
    - `phpstan.dist.neon` to `phpstan.neon`
    - `phpunit.xml.dist` to `phpunit.xml`
    - `behat.yml.dist` to `behat.yml`
    - `.php-cs-fixer.dist.php` to `.php-cs-fixer.php`
- Start the Docker container:
    - `make start-container`
    - `docker compose exec php composer install`
    - `docker compose exec php bin/console doctrine:migrations:migrate`

After setup, you can enter the container with:
```shell
make enter-container
```

There are some files inside `./docs/HttpAPI/` that you can use the .http files to test the API via REST Client in IntelliJ IDEA, VS Code, etc. 

## Running Tests (I recommend running all the make commands inside the container to avoid incompatibilities in different environments)

You can run the tests as needed:

- Unit Tests: ```make test-unit```
- Behat Tests: ```make test-behat```
- Architecture Tests: ```make test-architecture```

Or you can run all tests with:
```shell
make test-all
```

## Useful Commands

```shell
make help
```

## Project Focus

This project demonstrates Hexagonal Architecture (ports and adapters). The goal is to show how this structure makes the code cleaner, more testable, and easier to maintain.

### Key Points

- **Hexagonal Architecture**:
  - Separates core logic from external systems.
  - Makes it easy to replace external components without changing core logic.
  - Improves testability and maintainability.

- **Testing**:
  - **Architecture Tests**: Check that the project follows the Hexagonal Architecture principles.
  - **Unit Tests**: Test individual parts of the code.
  - **Behat Tests**: Test user interactions and workflows using BDD (Behavior Driven Development).

- **Code Quality**:
  - Uses `php-cs-fixer` to ensure consistent coding standards.
  - Generates API documentation with OpenAPI for easy understanding and integration.

- **Documentation**:
  - Includes API documentation in OpenAPI format.
  - Includes a README with project details and instructions.

- **Docker and Docker Compose**:
  - Uses Docker to ensure consistent development environments.
  - Uses Docker Compose to manage multiple containers.
  - Includes a `Makefile` with common commands for setup, testing, and development.

### Benefits and Considerations

- **Benefits**:
  - Clear separation of concerns.
  - Easier to test and maintain.
  - Flexible to changes in external systems.

- **Considerations**:
  - Might be more than needed for very small projects. I would use it for medium to large projects.

# Domain

This project was inspired by a relative's work in opening and managing dance studios. The domain involves managing studios, their rooms, equipment, and appointments.

## Domain Overview

### Main Entities

- **Studio**: Represents a dance studio with details like name, address, and contact info.
- **Room**: Represents a room within a studio, including its name, capacity, and equipment.
- **Equipment**: Represents items in a room, like sound systems and mirrors.
- **Appointment**: Represents bookings or events scheduled in a room.

## Implementation

The current implementation covers basic functionality. Additional features can be added to expand the project.

### Goals

The main goal was to showcase code structure and organization using Hexagonal Architecture, focusing on best practices and thought processes.

## Future Enhancements

- **Object Mother**: Add Object Mother pattern to simplify test data creation for the tests. I really did not like the way that I implemented the methods in the AbstractTestCase for the unit tests that return the objects. But I would implement the https://martinfowler.com/bliki/ObjectMother.html. 
- **Validations**: Add more validation and error handling. I really did not focus on this in the project.
- **Communication between different layers**: Improve communication between layers, like using DTOs.
- **OpenAPI Documentation**: The way I implemented works very well, but it can be improved to document objects and responses, and avoid code duplication. Also, I would add something like "Backstage" to render the documentation.
- **Expand Domain**: Add features like user management, notifications, and reporting.
- **Optimize Performance**: Improve the application's performance and scalability.
- **More examples with some message based communication**: I would like to show how to use messages (RabbitMQ or Kafka) to communicate between different services, and re-use the same domain logic.
- **CI/CD Pipeline**: Implement a CI/CD pipeline to automate testing and deployment. Maybe use some GitHub Actions.
- **Monitoring and Logging**: Add monitoring and logging to track application performance and errors.
