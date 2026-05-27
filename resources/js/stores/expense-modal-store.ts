import { Budget } from '@/types/budget'
import { Category } from '@/types/category'
import { create } from 'zustand'

type ExpenseModalStore = {
    open: boolean
    budget: Budget | null
    categories: Category[]
    openCreateModal: () => void
    closeModal: () => void
    setBudget: (budget: Budget) => void
    setCategories: (categories: Category[]) => void
}

export const useExpenseModalStore = create<ExpenseModalStore>(( set ) => ({
    open: false,
    budget: null,
    categories: [],
    openCreateModal: () => {
        set({
            open: true
        })
    },
    closeModal: () => {
        set({
            open: false
        })
    },
    setBudget: (budget) => {
        set({
            budget
        })
    },
    setCategories: (categories) => {
        set({
            categories
        })
    }
}))