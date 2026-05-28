export function formatDate(date: string) {
    return new Intl.DateTimeFormat('es-MX', {
        day: "numeric",
        month: 'long',
        year: 'numeric'
    }).format(new Date(date))
}

export function formatCorrency(amount: number) {
    return new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD'}).format(amount)
}