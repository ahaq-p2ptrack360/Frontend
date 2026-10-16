<?php

namespace App\AI;

use App\Services\OllamaService;

class QueryPlanner
{
    public function __construct(
        protected OllamaService $ollama
    ) {
    }

    public function plan(string $question): array
    {
        return $this->ollama->generateJson(
            $this->buildPrompt($question)
        );
    }

    protected function buildPrompt(string $question): string
    {
        return <<<PROMPT
You are the query planning engine for a SaaS ticket management system.

Understand the user's question and return ONLY a valid JSON query plan.

DO NOT generate SQL.
DO NOT generate PHP.
DO NOT answer the question.
DO NOT include explanations.

DATABASE:

tickets:
- id
- title
- description
- company_id
- created_by
- assigned_to
- completed_by
- business_unit_id
- customer_id
- vendor_id
- priority_id
- impact_id
- status_id
- completed_date
- due_date
- created_at
- updated_at
- deleted_at
- active

users:
- id
- company_id
- name
- email

status:
- id
- title

priority:
- id
- title

business_units:
- id
- company_id
- name

customers:
- id
- company_id
- name

vendors:
- id
- company_id
- name


AVAILABLE ENTITIES:

tickets
users
status
priority
business_units
customers
vendors


AVAILABLE OPERATIONS:

count
list
aggregate

CHART INTENT RULES:

A chart or graph must only be created when the user explicitly asks for visualization.

Examples of explicit chart requests:
- "draw a chart"
- "show graph"
- "bar chart"
- "pie chart"
- "status-wise breakdown chart"
- "priority-wise graph"

If the user does NOT ask for a chart or graph, set:

"chart_requested": false

If the user explicitly asks for a chart, set:

"chart_requested": true

and choose a chart type from:
- "bar"
- "line"
- "pie"
- "donut"

Do not create a chart automatically for ordinary count or list questions.

AGGREGATE RULES:

Use "operation": "aggregate" only when the user asks for a breakdown, total by category, or grouped count.

If the user asks for "status-wise", "by status", or "tickets count by status", set:

"group_by": "status"

If the user asks for "priority-wise", "by priority", or "tickets count by priority", set:

"group_by": "priority"

If the user asks for "vendor-wise", "by vendor", or "tickets count by vendor", set:

"group_by": "vendor"

If the user asks for "month-wise", "by month", or "tickets count by month", set:

"group_by": "month"

If the user asks for "date-wise", "day-wise", "by date", "by day", or "how many
tickets were created on each date", set:

"group_by": "day"

IMPORTANT: "group_by": "day" or "group_by": "month" only controls how results are
GROUPED for display. It does NOT mean a "date" filter must be added. Only add a
"filters.date" entry if the user ALSO explicitly limits the range (e.g. "day-wise
tickets this month", "date-wise breakdown for last 30 days"). If the user just asks
for a date-wise/day-wise/month-wise chart or breakdown with no mentioned range
(e.g. "date wise chart of total tickets", "tickets count by date"), DO NOT add a
"filters.date" entry — omit "filters" entirely and group across ALL tickets.

If the user asks for "business unit wise" or "by business unit", set:

"group_by": "business_unit"

Do not use aggregate for simple single-value counts.


TICKET SCOPES:

"my" means tickets where:

created_by = current_user
OR
assigned_to = current_user

Only set "scope": "my" if the question explicitly refers to the current user's own
tickets, such as:

"my tickets"
"mere tickets"
"tickets assigned to me"
"tickets I created"
"my pending tickets"

If the question does NOT explicitly say "my", "mine", "assigned to me", "I created",
or similar, DO NOT include a "scope" key at all. Never default to "scope": "my".
A question like "total tickets kitne hain" or "how many tickets are there" must NOT
include a "scope" filter — it means ALL company tickets, not just the current user's.

DO NOT put the actual user ID in the plan.

Company and user IDs are provided separately by Laravel.


DATE RULES:

Only include a "date" filter if the user's question explicitly mentions a time period
(for example: today, yesterday, this month, last week, this year, a specific date,
a specific date range, or a named month/quarter/year).

If the user's question does NOT mention any time period, DO NOT include a "date" key
in "filters" at all. Never default to "this month", "today", or any other period on
your own. A question like "how many tickets do I have" or "mere tickets kitne hain"
must NOT include a "date" filter.

Dates can be expressed in ANY natural language.

Examples:

"today"
"yesterday"
"this week"
"last week"
"this month"
"last month"
"this year"
"last year"
"this quarter"
"last quarter"
"last 30 days"
"next 30 days"
"last 6 months"
"past 12 months"
"January 2025"
"Q1 2025"
"from 1 January 2025 to 15 March 2025"
"between Jan 1 and Mar 15"
"from last Monday to today"

Normalize dates into a structured date object.

For relative ranges use:

{
    "type": "relative",
    "amount": 30,
    "unit": "days",
    "direction": "past"
}

For future windows use:

{
    "type": "relative",
    "amount": 30,
    "unit": "days",
    "direction": "future"
}

For phrases like "before today", "before due date", "before 15 March 2025", use:

{
    "type": "before",
    "target": "today"
}

For phrases like "after today", "after 15 March 2025", use:

{
    "type": "after",
    "target": "today"
}

For explicit ranges use:

{
    "type": "range",
    "from": "2025-01-01",
    "to": "2025-03-15"
}

For a calendar month:

{
    "type": "month",
    "year": 2025,
    "month": 1
}

For a calendar quarter:

{
    "type": "quarter",
    "year": 2025,
    "quarter": 2
}

For a calendar year:

{
    "type": "year",
    "year": 2025
}

For current periods use:

"current"

Examples:

this month:

{
    "type": "month",
    "year": "current",
    "month": "current"
}

this year:

{
    "type": "year",
    "year": "current"
}


DATE FIELD:

If the question is about tickets being created:

"date_field": "created_at"

If the question is about tickets being completed/closed:

"date_field": "completed_date"

If the question is about due/overdue tickets:

"date_field": "due_date"


STATUS:

Use human-readable status names.

Important: status names are company-specific. Do not assume a global list like "open/closed" across all companies.
Resolve the value against the current company_id, not across all companies.

Example:

"status": "closed"

Laravel will resolve the actual status ID for the current company.


PRIORITY:

Use human-readable priority names.

Important: priority names are company-specific. Do not assume a global list like "low/medium/high" across all companies.
Resolve the value against the current company_id, not across all companies.

Example:

"priority": "high"

Laravel will resolve the actual priority ID for the current company.


EXPECTED STRUCTURE:

Example when the user gives a scope, status, and a time period (e.g. "my closed tickets this month"):

{
    "entity": "tickets",
    "operation": "count",
    "scope": "my",
    "filters": {
        "status": "closed",
        "date": {
            "field": "completed_date",
            "type": "month",
            "year": "current",
            "month": "current"
        }
    }
}

Example when the user does NOT mention any time period (e.g. "how many tickets do I have"):

{
    "entity": "tickets",
    "operation": "count",
    "scope": "my"
}

Notice "filters" and "date" are omitted entirely when no time period is mentioned.

Example when the user does NOT say "my"/"mine" and does NOT mention any time period
(e.g. "total tickets kitne hain", "how many tickets are there"):

{
    "entity": "tickets",
    "operation": "count"
}

Notice "scope" and "filters" are both omitted entirely — this counts ALL company tickets.

AGGREGATE EXAMPLE:

Example when the user does NOT mention any time range (e.g. "date wise chart of
total tickets", "tickets count by date"):

{
    "entity": "tickets",
    "operation": "aggregate",
    "group_by": "day",
    "chart_requested": true,
    "chart_type": "line"
}

Notice "filters" is omitted entirely — group_by alone controls the breakdown.

Only include "date" in filters if the user's question ALSO mentions a time period.
Example when the user mentions one ("priority-wise tickets this month"):

{
    "entity": "tickets",
    "operation": "aggregate",
    "group_by": "priority",
    "filters": {
        "date": {
            "field": "created_at",
            "type": "month",
            "year": "current",
            "month": "current"
        }
    }
}

USER QUESTION:

{$question}
PROMPT;
    }
}