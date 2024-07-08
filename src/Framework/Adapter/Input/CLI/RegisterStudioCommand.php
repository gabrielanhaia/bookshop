<?php

namespace App\Framework\Adapter\Input\CLI;

use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Shared\StudioDTO;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Example(s) of execution:
 *  - php bin/console app:register_studio --name="Sun Studio" --street="Street 1" --city="City 1" --zipCode="12345" --country="US" --email="contact@sunstudio.com"
 *  - php bin/console app:register_studio --name="Bier Studio" --street="BierStrasse 2" --city="Berlin" --zipCode="54321" --country="DE" --email="contact@bierstudio.com"
 */
#[AsCommand(
    name: 'app:register_studio',
    description: 'Register a new studio'
)]
class RegisterStudioCommand extends Command
{
    public function __construct(
        private readonly ValidatorInterface    $validator,
        private readonly RegisterNewStudioPort $registerNewStudioPort
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'The name of the studio')
            ->addOption('street', null, InputOption::VALUE_REQUIRED, 'The street of the studio')
            ->addOption('city', null, InputOption::VALUE_REQUIRED, 'The city of the studio')
            ->addOption('zipCode', null, InputOption::VALUE_REQUIRED, 'The zip code of the studio')
            ->addOption('country', null, InputOption::VALUE_REQUIRED, 'The country of the studio')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'The email of the studio')
            ->setDescription('This command allows you to register a new studio.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Register a new studio');

        $name = $input->getOption('name');
        $street = $input->getOption('street');
        $city = $input->getOption('city');
        $zipCode = $input->getOption('zipCode');
        $country = $input->getOption('country');
        $email = $input->getOption('email');

        $studioDTO = StudioDTO::create(
            $name,
            $street,
            $city,
            $zipCode,
            $country,
            $email
        );
        $this->validate($studioDTO);

        $studioDTO = $this->registerNewStudioPort->registerNewStudio($studioDTO);
        $io->success('Studio registered with ID: ' . $studioDTO->getId()->toRfc4122());

        return Command::SUCCESS;
    }

    /**
     * Note: Such validation could be extracted to another place, and re-used, but I am keeping it here for simplicity.
     * The important part is that the validation is done before the use case is called as it would happen in a request validation (HTTP).
     */
    private function validate(StudioDTO $studioDTO): void
    {
        $violations = $this->validator->validate($studioDTO);

        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }

            throw new \InvalidArgumentException(implode("\n", $errors));
        }
    }
}
