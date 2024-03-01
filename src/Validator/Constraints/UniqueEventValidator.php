<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event; // Assurez-vous que cette ligne est présente et correctement orthographiée

class UniqueEventValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $existingEvent = $this->entityManager->getRepository(Event::class)->findOneBy(['titre' => $value]);

        if ($existingEvent) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
