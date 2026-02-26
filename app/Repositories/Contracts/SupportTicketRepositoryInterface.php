<?php

namespace App\Repositories\Contracts;

interface SupportTicketRepositoryInterface
{
    public function getAll(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function addReply(int $ticketId, array $data);
    public function getReplies(int $ticketId);
}
