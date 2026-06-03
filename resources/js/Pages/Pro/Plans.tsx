import PricingTable from "@/Components/PricingTable";
import { Head } from "@inertiajs/react";


export default function Plans() {
    return (
        <>
            <Head title="Planes" />

            <main className="mt-5">
                <PricingTable />
            </main>
        </>
    )
}