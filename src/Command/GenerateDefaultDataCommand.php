<?php

namespace App\Command;

use App\Service\DataGenerator\DataGenerator;
use App\Service\DataGenerator\DataGeneratorFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'db:generate-default-data',
    description: 'Generates dummy data for the database',
    hidden: false
)]
class GenerateDefaultDataCommand extends Command
{
    private const VALID_MODE_OPTION_VALUES = [ 'fake', 'api' ];

    private ?DataGenerator $dataGenerator = null;

    private ?DataGeneratorFactory $dataGeneratorFactory;

    public function __construct(DataGenerator $dataGenerator, DataGeneratorFactory $dataGeneratorFactory)
    {
        parent::__construct();
        
        $this->dataGenerator = $dataGenerator;
        $this->dataGeneratorFactory = $dataGeneratorFactory;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setHelp('This command allows you to generate dummy data for your database')
            ->addOption(
                'mode',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Use fake to generate data with faker and api to use the json placeholder API'
            )
            ->addOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'The limit of items to generate in the data generator'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output) : int
    {
        $mode = $input->getOption('mode');
        $limit = $input->getOption('limit');

        if ($mode) {
            if (!$this->isValidMode($mode)) {
                $output->writeln('<error>The value for the mode option is invalid.</error>');
                return self::INVALID;
            }

            if ($mode === 'api' && $limit) {
                $output->writeln('<comment>The limit number will be ignored because the api returns a fixed number of items</comment>');
            }

            $this->dataGenerator = $this->dataGeneratorFactory->getDataGenerator($mode, $limit);
        }

        try {
            $this->dataGenerator->generate();
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return self::FAILURE;
        }

        $output->writeln('<info>The data was successfully generated</info>');

        return self::SUCCESS;
    }

    /**
     * Checks if the mode passed from CLI is valid.
     */
    private function isValidMode(string $mode) : bool
    {
        return in_array($mode, self::VALID_MODE_OPTION_VALUES);
    }
}