<?php
/**
 * File: HealthcareProfessionalService.php
 * Description: Service to handle healthcare professional operations
 *
 * Created By: Vivek Singh
 * Created At: 2025-10-08
 * Updated By: Vivek Singh
 * Updated At: 2025-10-08
 *
 * @package App\Services
 * @author Vivek Singh
 */
namespace App\Services;

use App\Repositories\Interfaces\HealthcareProfessionalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class HealthcareProfessionalService
{
    public function __construct(
        private HealthcareProfessionalRepositoryInterface $professionalRepository
    ) {}

    public function getAllProfessionals(): Collection
    {
        return $this->professionalRepository->getAll();
    }

    public function getAvailableProfessionals(): Collection
    {
        return $this->professionalRepository->getAvailable();
    }

    public function getProfessionalsBySpecialty(string $specialty): Collection
    {
        return $this->professionalRepository->getBySpecialty($specialty);
    }
}
