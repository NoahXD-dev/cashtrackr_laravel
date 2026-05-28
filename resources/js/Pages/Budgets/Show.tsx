import { Head, usePage } from "@inertiajs/react"
import { ToastContainer, toast } from "react-toastify"
import { Budget } from "@/types/budget"
import AmountDisplay from "@/Components/AmountDisplay"
import ExpenseModal from "@/Components/ExpenseModal"
import { useExpenseModalStore } from "@/stores/expense-modal-store"
import { Category } from "@/types/category"
import { useEffect, useState } from "react"
import { formatDate } from "@/utils/main"
import ProgressBar from "@/Components/ProgressBar"
import ExpenseDropdown from "@/Components/ExpenseDropdown"
import DeleteExpenseModal from "@/Components/DeleteExpenseModal"
import CashTrackrAgent from "@/Components/CashTrackrAgent"

type Props = {
    budget: Budget
    categories: Category[]
    spent: string
}

export default function Show({ budget, categories, spent }: Props) {
    const { flash } = usePage().props
    useEffect(() => {
        if(flash.success) {
            toast.success(flash.success)
        }
    }, [flash])

    const openCreateModal = useExpenseModalStore((state => state.openCreateModal))

    const remaining = +budget.amount - +spent
    const percentageUsed = +((+spent / +budget.amount) * 100).toFixed(2)
    const [progress, setProgress] = useState(0)

    useEffect(() => {
        const timeout = setTimeout(() => {
            setProgress(percentageUsed)
        }, 100);

        return () => clearTimeout(timeout)
    }, [percentageUsed])

    useEffect(() => {
        useExpenseModalStore.getState().setBudget(budget)
        useExpenseModalStore.getState().setCategories(categories)
    }, [budget, categories])

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
                <ProgressBar percentageUsed={progress} />
                <div className='space-y-5'>
                    <AmountDisplay label="Presupuesto" amout={+budget.amount} />
                    <AmountDisplay label="Gastado" amout={+spent} />
                    <AmountDisplay label="Restante" amout={remaining} />
                </div>  
            </main>

            <section className="p-10 lg:px-5 shadow-lg mt-10">
                <div className="flex items-center justify-between">
                    <h2 className="text-3xl font-bold">Gastos</h2>

                    <button className="bg-purple-950 hover:bg-purple-800 px-5 py-2 my-5 rounded-lg text-white font-bold text-xl cursor-pointer" onClick={openCreateModal}>
                        Nuevo gasto
                    </button>
                </div>

                { budget.expenses.length ? (
                    <div className="mt-8 flow-root ">
                        <div className=" ring-1 ring-gray-300 rounded-lg ">
                            <div className="inline-block min-w-full align-middle">
                                <table className="relative min-w-full">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <span className="sr-only">Gastos</span>
                                            </th>
                                            <th scope="col">
                                                <span className="sr-only">Acciones</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-300 ">
                                        { budget.expenses.map(expense => (
                                            <tr key={ expense.id } className='flex justify-between items-center '>
                                                <td className={`${budget.type === 'general' ? 'pt-10' : 'pt-5'} pb-5 px-10 relative`}>
                                                    { budget.type == 'general' && (
                                                        <p className={`absolute top-0 left-0 inline-block px-3 py-1 rounded-br-2xl text-sm font-medium w-40 ${ expense.category_color }`}>
                                                            { expense.category_label }
                                                        </p>
                                                    ) }
                                                    <p className="text-xl font-bold text-gray-500">{ expense.name }</p>
                                                    <p className="text-lg text-gray-500">${ expense.amount }</p>
                                                    <p className='text-sm text-gray-400'>Agregado el: { formatDate(expense.created_at) }</p>
                                                </td>
                                                <td className="py-6 px-10 flex justify-end gap-3">
                                                    <ExpenseDropdown expense={expense} />
                                                </td>
                                            </tr>
                                        )) }
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                ) : (
                    <p className="text-center text-xl mt-10 ">No Hay Gastos.
                        <button
                            type='button'
                            onClick={openCreateModal}
                            className="text-amber-500"
                        >Comienza creando uno</button>
                    </p>
                ) }
            </section>

            <CashTrackrAgent budgetId={budget.id} />
            <ExpenseModal />
            <DeleteExpenseModal />
            <ToastContainer />
        </>
    )
}
