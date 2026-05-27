import { formatCorrency } from "@/utils/main"

type Props = {
    label: string
    amout: number
} 

export default function AmountDisplay({ label, amout }: Props) {
    return (
        <p className="text-3xl font-bold text-purple-950">
            { label }: { '' }
            <span className="font-black text-amber-500">{ formatCorrency(amout) }</span>
        </p>
    )
}