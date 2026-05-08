<?php

namespace App\Support;

class CrmOptions
{
    public static function roles(): array
    {
        return [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'sales_manager' => 'Sales Manager',
            'sales_exec' => 'Sales Executive',
            'support_agent' => 'Support Agent',
        ];
    }

    public static function accountStatuses(): array
    {
        return [
            'active' => 'Active',
            'prospect' => 'Prospect',
            'paused' => 'Paused',
        ];
    }

    public static function contactLifecycleStages(): array
    {
        return [
            'lead' => 'Lead',
            'qualified' => 'Qualified',
            'customer' => 'Customer',
            'champion' => 'Champion',
        ];
    }

    public static function leadStatuses(): array
    {
        return [
            'new' => 'New',
            'working' => 'Working',
            'qualified' => 'Qualified',
            'nurturing' => 'Nurturing',
            'converted' => 'Converted',
            'lost' => 'Lost',
        ];
    }

    public static function leadSources(): array
    {
        return [
            'manual' => 'Manual',
            'website' => 'Website',
            'referral' => 'Referral',
            'campaign' => 'Campaign',
            'import' => 'Import',
        ];
    }

    public static function taskPriorities(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }

    public static function taskStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'blocked' => 'Blocked',
            'completed' => 'Completed',
        ];
    }

    public static function ticketPriorities(): array
    {
        return self::taskPriorities();
    }

    public static function ticketStatuses(): array
    {
        return [
            'open' => 'Open',
            'waiting' => 'Waiting on Customer',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];
    }

    public static function dealStatuses(): array
    {
        return [
            'open' => 'Open',
            'won' => 'Won',
            'lost' => 'Lost',
        ];
    }

    public static function taskRelatedTypes(): array
    {
        return [
            'lead' => \App\Models\Lead::class,
            'contact' => \App\Models\Contact::class,
            'deal' => \App\Models\Deal::class,
            'ticket' => \App\Models\SupportTicket::class,
        ];
    }
}
