<?php

namespace App\SalesDelivery\Application\Commands\RecordDeliveryMeasurement;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MeasurementDTO
{
  public function __construct(
    #[Assert\NotBlank(message: "Tank must be not null")]
    public string $tankId,

    #[Assert\PositiveOrZero(message: "Start must be >= 0")]
    public ?int $start = null,

    #[Assert\PositiveOrZero(message: "End must be >= 0")]
    public ?int $end = null,

    #[Assert\Positive(message: "Quantity must be > 0")]
    public ?int $quantity = null,
  ) {}

  #[Assert\Callback]
  public function validate(ExecutionContextInterface $context): void
  {
    if ($this->quantity !== null) {
      if ($this->start !== null || $this->end !== null) {
        $context->buildViolation("You cannot provide both quantity and start/end.")
          ->addViolation();
      }
    } else {
      if ($this->start === null || $this->end === null) {
        $context->buildViolation("You must provide either quantity OR start and end.")
          ->addViolation();
      }

      if ($this->start < $this->end) {
        $context->buildViolation("Start counter must not be less than End counter.")
          ->addViolation();
      }
    }
  }
}
