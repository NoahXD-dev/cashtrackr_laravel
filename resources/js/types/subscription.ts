type StatusLabel = {
    text: string;
    description: string;
    date: string | null;
    color: 'green' | 'yellow' | 'orange' | 'red' | 'gray';
}

export type  Subscription = {
    plan: 'monthly' | 'yearly';
    status: string;
    on_grace_period: boolean;
    canceled: boolean;
    past_due: boolean;
    ends_at: string | null;
    next_billing_date: string | null;
    price: number;
    status_label: StatusLabel;
}