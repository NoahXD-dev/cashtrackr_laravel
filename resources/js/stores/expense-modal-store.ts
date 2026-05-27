import { create } from 'zustand'

type ExpenseModalStore = {
    open: boolean
}

export const useExpenseModalStore = create<ExpenseModalStore>(() => ({
    open: true
}))