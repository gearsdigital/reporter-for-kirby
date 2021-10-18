<?php

namespace KirbyReporter\Client;

interface ClientInterface
{
    public function createIssue(array $requestBody);
}
