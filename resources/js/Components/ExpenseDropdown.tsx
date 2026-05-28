import { useDeleteExpenseStore } from '@/stores/expense-delete-store'
import { useExpenseModalStore } from '@/stores/expense-modal-store'
import { Expense } from '@/types/expense'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/react'

type Props = {
    expense: Expense
}

export default function ExpenseDropdown({ expense }: Props) {
  const openEditModal = useExpenseModalStore(state => state.openEditModal)
  const openDeleteModal = useDeleteExpenseStore(state => state.openModal)

  return (
    <Menu as="div" className="relative inline-block ">
      <MenuButton className="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50">
        Opciones
      </MenuButton>

      <MenuItems
        transition
        className="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg outline-1 outline-black/5 transition data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in"
      >
        <div className="py-1">
          <MenuItem>
            <button
              type="button"
              onClick={() => openEditModal(expense)}
              className="group flex w-full items-center px-4 py-2 text-left text-sm text-gray-700 data-focus:bg-gray-100 data-focus:text-gray-900"
            >
              Editar
            </button>
          </MenuItem>

          <MenuItem>
            <button
              onClick={() => openDeleteModal(expense)}
              className="group w-full flex items-center px-4 py-2 text-sm text-gray-700 data-focus:bg-gray-100 data-focus:text-gray-900 data-focus:outline-hidden"
            >
              Eliminar
            </button>
          </MenuItem>
        </div>
      </MenuItems>
    </Menu>
  )
}
