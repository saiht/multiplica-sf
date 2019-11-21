<?php

namespace App\Command;

use App\Entity\Color;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SeedColorsCommand extends Command
{
    private const COLOR_COLUMNS = ['id', 'color', 'name', 'year', 'pantone_value'];
    protected static $defaultName = 'app:seed-colors';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(static::$defaultName);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Seed database color table')
            ->addArgument('file', InputArgument::REQUIRED, 'File path of your csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($filePath = $input->getArgument('file')) {
            if (($handle = fopen($filePath, 'rb')) !== false) {
                $headers = fgetcsv($handle);
                if (!empty(array_diff($headers, self::COLOR_COLUMNS))) {
                    throw new \Exception('The csv provided does not have all needed columns');
                }
                while (!feof($handle) && ($data = fgetcsv($handle)) !== false) {
                    if (count($headers) !== count($data)) {
                        throw new \Exception('Invalid csv file');
                    }
                    $color = array_combine($headers, $data);
                    // removing not needed id column
                    array_shift($color);
                    $this->entityManager->persist((new Color)
                        ->setColor($color['color'])
                        ->setName($color['name'])
                        ->setPantoneValue($color['pantone_value'])
                        ->setYear($color['year'])
                    );
                }
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('File %s imported in "color" table', $filePath));

        return 0;
    }
}
