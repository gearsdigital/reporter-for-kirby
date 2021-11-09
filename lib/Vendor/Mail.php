<?php

namespace KirbyReporter\Vendor;

class Mail implements VendorInterface
{

    private string $from;
    private string $to;
    private string $subject;
    private ?string $type;

    public function __construct(string $from, string $to, string $subject, string $type = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->type = $type;
    }

    public function getName(): string
    {
        return 'mail';
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getType(): ?string
    {
        if (in_array($this->type, ['html', 'text'])) {
            return $this->type;
        }

        return 'text';
    }
}
