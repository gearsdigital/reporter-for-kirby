<?php

namespace KirbyReporter\Model;

class FormData
{
    private string $title;
    private array $formFields;

    public function __construct(array $formData)
    {
        $this->setTitle($formData['title'] ?? '');
        $this->setFormFields($formData['formFields'] ?? ['formFields' => []]);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    private function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getFormFields(): array
    {
        return $this->formFields;
    }

    private function setFormFields(array $formFields): void
    {
        $this->formFields = $formFields;
    }
}
