import { Head } from "@inertiajs/react"
import { Budget } from "@/types/budget"
import AmountDisplay from "@/Components/AmountDisplay"

type Props = {
    budget: Budget
}

export default function Show({ budget }: Props) {

    return (
        <>
            <Head title={`Presupuesto: ${budget.name}`} />
            
            <section className="sm:flex sm:items-center mt-10">
                <div className="sm:flex-auto">
                    <h1 className="font-bold text-4xl">Presupuesto: {budget.name}</h1>
                    <p className="mt-2 text-xl text-gray-500">Maneja tu Presupuesto, añade, quita o edita tus gastos aquí.</p>
                </div>
                <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <a
                        href={'/dashboard'}
                        className="block bg-amber-500 text-white w-full px-5 py-3 rounded-lg  font-bold  text-xl cursor-pointer text-center">Volver a Presupuestos</a>
                </div>
            </section>

            <main className='grid grid-cols-1 md:grid-cols-2 items-center gap-20 mt-10'>

                <div className='space-y-5'>
                    <AmountDisplay label="Presupuesto" amout={+budget.amount} />
                    <AmountDisplay label="Gastado" amout={0} />
                    <AmountDisplay label="Restante" amout={0} />
                </div>  
            </main>
        </>
    )
}
