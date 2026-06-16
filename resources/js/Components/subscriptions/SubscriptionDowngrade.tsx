import { Subscription } from "@/types/subscription"
import { formatDate } from "@/utils/main"

type Props = {
    next_billing_date: Subscription['next_billing_date']
    ends_at: Subscription['ends_at']
}

export default function SubscriptionDowngrade({ next_billing_date, ends_at }: Props) {
    return (
        <div className="rounded-xl  bg-indigo-600 p-6 mb-6">
            <h3 className="text-2xl font-bold mb-1 text-white">
                Estás en el plan Anual
            </h3>
            <p className="text-white">
                Si deseas cambiar a mensual, puedes cancelar tu suscripción
                actual. Mantendrás acceso hasta el { formatDate(ends_at || next_billing_date) }
                y después podrás suscribirte al plan mensual.
            </p>
        </div>
    )
}