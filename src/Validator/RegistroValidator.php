<?php

namespace App\Validator;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RegistroValidator extends ConstraintValidator
{
    private $registroRepository;
    public function __construct(UserRepository $registroRepository ){
        $this-> registroRepository=$registroRepository;
    }
    public function validate($registro, Constraint $constraint)
    {
        /* @var App\Validator\Registro $constraint */
        $descripcion =$registro->getEmail();
        if (null === $descripcion || '' === $descripcion) {
            return;
        }

        $registroExistente= $this-> registroRepository->findOneByDescription();
        // TODO: implement the validation here
        if(null !== $registroExistente && $registro->getId() !== $registroExistente->getId()){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $descripcion)
                ->addViolation();
        }
    }
}
