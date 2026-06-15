import { Subscription } from "@/types/subscription"
import { formatDate } from "@/utils/main"

type Props = {
    isYearly: boolean
    color: string
    status_label: Subscription['status_label']
    price: Subscription['price']
}

export default function SubscriptionStatus({ isYearly, color, status_label, price }: Props) {
    return (
        <div className="rounded-xl border border-slate-300  p-6 mb-6">
            <div className="flex justify-between items-start mb-4">
                <div>
                    <span className="text-sm text-gray-500 uppercase tracking-wide">
                        Plan actual
                    </span>
                    <h2 className="text-2xl font-bold flex items-center gap-2 mt-1">
                        PRO { isYearly ? 'Anual' : 'Mensual' }
                    </h2>
                </div>

                <div className="text-right">
                    <div className="text-3xl font-black">
                        ${ price }
                        <span className="text-base font-normal text-gray-500">/{ isYearly ? 'año' : 'mes' }</span>
                    </div>
                </div>
            </div>

            <div className="border-t border-slate-300 pt-4 space-y-2 text-sm">
                <div className={`rounded-lg border p-4 mb-4 ${ color }`}>
                    <div className="font-bold text-xl">{ status_label.text }</div>
                    { status_label.date ? (
                        <p className="text-black text-lg">
                            { status_label.description } { ' ' }
                            <span className="font-medium">
                                { formatDate(status_label.date) }
                            </span>
                        </p>
                    ) : (
                        <p>{ status_label.description }</p>
                    )}
                </div>
            </div>
        </div>
    )
}