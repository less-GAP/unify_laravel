import menuAside from "@/menuAside";
import router from "@/router";
import {useAuthStore} from "@/stores/auth";
import Api from "@/utils/Api";

function SearchApplication(term) {
  const authStore = useAuthStore();

  return new Promise(resolve => {
    let result = menuAside
      .filter(menu => {
        return !menu.disable && menu.to && authStore.hasPermission(menu.permission)
      })
      .filter(menu => {
        return !term || (menu.label.toLowerCase().includes(term.toLowerCase()))
      }).map(item => {
        return {
          label: item.label,
          icon: item.icon,
          action() {
            router.push(item.to)
          }
        }
      })
    resolve(result.slice(0, 3));
  })

}

function SearchPatients(term) {
  return new Promise(resolve => {
      Api.get('/patient/all?limit=3&filter[search]=' + (term ? term : '')).then(rs => {
        resolve(rs.data.map(item => {
          return {
            label: item.full_name,
            action() {
              router.replace('/patient/' + item.id + '/detail')
            }
          }
        }))
      })
    }
  )
}

function SearchDoctors(term) {
  return new Promise(resolve => {
      Api.get('/doctor/all?limit=3&filter[search]=' + (term ? term : '')).then(rs => {
        resolve(rs.data.map(item => {
          return {
            label: item.full_name,
            action() {
              router.replace('/doctor/' + item.id + '/edit')
            }
          }
        }))
      })
    }
  )
}

const SearchItems = [
  {
    label: 'Applications',
    search: SearchApplication
  },
  {
    label: 'Patients',
    permission: 'patient.list',
    search: SearchPatients
  }, {
    label: 'Doctors',
    permission: 'doctor.list',
    search: SearchDoctors
  }
]
export default SearchItems
