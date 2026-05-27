import { Budget } from '@/types/budget'
import { Category } from '@/types/category'
import { Expense } from '@/types/expense'
import { create } from 'zustand'

type ExpenseModalStore = {
    open: boolean
    budget: Budget | null
    expense: Expense | null
    categories: Category[]
    openCreateModal: () => void
    openEditModal: (expense: Expense) => void
    closeModal: () => void
    setBudget: (budget: Budget) => void
    setCategories: (categories: Category[]) => void
}

export const useExpenseModalStore = create<ExpenseModalStore>(( set ) => ({
    open: false,
    budget: null,
    expense: null,
    categories: [],
    openCreateModal: () => {
        set({
            open: true
        })
    },
    openEditModal: (expense) => {
        set({
            open: true,
            expense
        })
    },
    closeModal: () => {
        set({
            open: false,
            expense: null
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