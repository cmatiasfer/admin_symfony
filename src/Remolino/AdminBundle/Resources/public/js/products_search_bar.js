let section

// eslint-disable-next-line no-unused-vars
const isLoading = () => {
  const html = `
    <div class="preload">
      <div class="bubblingG">
        <span id="bubblingG_1"></span>
        <span id="bubblingG_2"></span>
        <span id="bubblingG_3"></span>
      </div>
    </div>
  `
  return html
}

const setItemLocalStorage = (button) => {
  const objForm = {}
  $('.save_in_ls').each((index, element) => {
    if ($(element).val() !== '') {
      const keyLs = $(element).attr('id')
      const listClass = $(element).attr('class').split(' ')

      if (listClass[0] !== '__autocomplete') {
        objForm[keyLs] = $(element).val()
      } else {
        objForm[keyLs] = { id: $(element).attr('data-id'), name: $(element).val() }
      }
    }
  })
  if (button !== 'paginate') {
    const pageActive = $('.paginate_button.active').children('a').attr('data-dt-idx')
    // eslint-disable-next-line no-underscore-dangle
    objForm._page = pageActive
  }

  localStorage.setItem(`search_bar_${section}`, JSON.stringify(objForm))
}

$(() => {
  section = $('.content-table').attr('data-name')
  // Busqueda en Tablas
  const name = $('form').attr('name')
  $(`form[name='${name}']`).submit((event) => {
    event.preventDefault()
    const setLS = $('#buscar_tabla').attr('data-setls')
    if (setLS === 'y') {
      setItemLocalStorage()
    }
    $('#buscar_tabla').addClass('disabled')
    setTimeout(() => {
      $('#indexlist').DataTable().clear().destroy()
      // eslint-disable-next-line no-undef
      $('#indexlist').DataTable(getConfigDataTable())
    }, 800)
  })

  const LSform = localStorage.getItem('search_bar_products')

  // Si existe item search_bar_products
  // los datos de este objeto los envio al form
  if (LSform) {
    const listLSForm = JSON.parse(LSform)
    // eslint-disable-next-line no-restricted-syntax,prefer-const
    for (let property in listLSForm) {
      if (property !== '_page') {
        if (typeof listLSForm[property] === 'string') {
          $(`#${property}`).val(listLSForm[property])
          $(`#${property}`).not('[data-path], .convert_options_to_json').children().removeAttr('selected', 'selected')
          $(`#${property}`)
            .not('[data-path], .convert_options_to_json')
            .children(`option[value="${listLSForm[property]}"]`)
            .attr('selected', 'selected')
        } else {
          $(`#${property}`).val(listLSForm[property].name)
          $(`#${property}`).attr('data-id', listLSForm[property].id)

          const listClass = $(`#${property}`).attr('class').split(' ')

          // Cuando data-path no existe, el input autocomplete esta conectado a EntityType
          // Si el input autocomplete esta conectado a un EntityType
          // busco la opcion correspondiente dentro del select
          if ($(`#${property}`).attr('data-path') === undefined) {
            const selectedClass = $(`[data-elementautocomplete="${listClass[1]}"]`)
            selectedClass.children('option').attr('selected', false)
            selectedClass.children(`option[value="${listLSForm[property].id}"]`).attr('selected', 'selected')
          }
        }
      }
    }
    // cuando exista data en LS no vuelvo a settear ls
    $('#buscar_tabla').attr('data-setls', 'n')
    $('#buscar_tabla').click()
    // Luego de terminar el evento submit, este mismo evento vuelve a su estado "normal"
    $('#buscar_tabla').attr('data-setls', 'y')
  }

  // Cuando realizo click sobre el paginado, en item search_bar_products
  // borro key _page
  $(document).on('click', '.paginate_button > a', (event) => {
    event.preventDefault()
    $('#_mode').val('show')
    $('#_list').val('')
    $('.container-info-selected').hide()
    $('.container-info-selected .data').find('count').text('0')
    setItemLocalStorage('paginate')
  })

  // Cuando hago un click en btn-edit, actualizo/creo data localstorage
  $(document).on('click', '.mode-edit', (event) => {
    event.preventDefault()
    setItemLocalStorage()
    // eslint-disable-next-line no-restricted-globals
    location.href = $(event.currentTarget).attr('href')
    return false
  })
  $(document).on('click', '.form-reset', (event) => {
    event.preventDefault()
    localStorage.removeItem(`search_bar_${section}`)

    // eslint-disable-next-line no-restricted-globals
    location.href = $(event.currentTarget).attr('href')
    return false
  })
})
