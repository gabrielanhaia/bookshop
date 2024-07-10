<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Exception\StudioAlreadyExistsException;
use App\Application\Factory\StudioFactory;
use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Application\Port\Output\TransactionHandlerPort;
use App\Application\Port\Shared\StudioDTO;

class RegisterNewStudioUseCase implements RegisterNewStudioPort
{
    public function __construct(
        private readonly TransactionHandlerPort $transactionHandlerPort,
        private readonly StudioRepositoryPort   $studioRepository,
        private readonly StudioFactory          $studioFactory
    ) {
    }

    public function registerNewStudio(StudioDTO $studioDTO): StudioDTO
    {
        return $this->transactionHandlerPort->execute(function () use ($studioDTO) {
            $this->validateIfStudioExists($studioDTO);
            $studioAggregate = $this->studioFactory->createStudioAggregateFromDTO($studioDTO);
            $this->studioRepository->saveStudio($studioAggregate);
            return $studioDTO->setId($studioAggregate->getId());
        });
    }

    /**
     * @throws StudioAlreadyExistsException if studio already exists
     */
    private function validateIfStudioExists(StudioDTO $studioDTO): void
    {
        $studioAggregate = $this->studioRepository
            ->findStudioByName($studioDTO->getName());

        if ($studioAggregate !== null) {
            throw new StudioAlreadyExistsException('Studio already exists');
        }
    }
}
