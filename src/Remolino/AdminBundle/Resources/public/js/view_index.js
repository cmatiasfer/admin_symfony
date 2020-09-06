let value
let paramForm

const getConfigDataTable = () => {
  paramForm = {
    _mode: $('#_mode').val(),
    _list: $('#_list').val(),
    _section: $('#_section').val(),
  }

  $('form .send_param').each((index, element) => {
    value = $(element).val()
    // ES UN SELECT CONECTADO A UN ENTITY TYPE
    if ($(element).children('option')[0]) {
      value = $(element).children("option[selected='selected']").val()
    }
    // Input en espera de respuesta ajax para mostrar resultados
    if ($(this).attr('data-path')) {
      value = $(this).attr('data-id')
    }

    if ($(this).attr("type='checkbox'")) {
      value = $('#products_search_bar_visible').is(':checked') ? 1 : 0
    }

    paramForm = {
      _mode: $('#_mode').val(),
      _list: $('#_list').val(),
      _section: $('#_section').val(),
    }

    $('form .send_param').each((i, e) => {
      value = $(e).val()
      // ES UN SELECT CONECTADO A UN ENTITY TYPE
      if ($(e).children('option')[0]) {
        value = $(e).children("option[selected='selected']").val()
      }
      // Input en espera de respuesta ajax para mostrar resultados
      if ($(e).attr('data-path')) {
        value = $(e).attr('data-id')
      }

      if ($(e).attr("type='checkbox'")) {
        value = $('#products_search_bar_visible').is(':checked') ? 1 : 0
      }

      paramForm[$(e).attr('id')] = value
    })
  })

  const obj = {
    serverSide: true,
    processing: true,
    ajax: {
      url: $('input[data-url]').attr('data-url'),
      contentType: 'application/json',
      data: paramForm
    },
    columns: JSON.parse($('input[data-json]').attr('data-json')),
    pageLength: $('input[data-pagelength]').attr('data-pagelength'),
    paging: true,
    searching: false,
    lengthChange: false,
    ordering: false,
    autoWidth: false,
    drawCallback: () => {
      $('#_mode').val('show')
      $('#_list').val('')
      $('.container-info-selected').hide()
      $('.container-info-selected .data').find('count').text('0')
      const LSform = localStorage.getItem('search_bar_products')
      if (LSform) {
        const listLSForm = JSON.parse(LSform)
        // eslint-disable-next-line no-restricted-syntax
        for (const property in listLSForm) {
          if (property === '_page') {
            setTimeout(() => {
              $(`[data-dt-idx="${listLSForm[property]}"]`).click()
            }, 600)
          }
        }
      }
      $('#buscar_tabla').removeClass('disabled')
    },
    columnDefs: [{ width: '50px', targets: 0 }]
  }

  return obj
}

$(window).scroll((event) => {
  if ($(event.currentTarget).scrollTop() < 50) {
    $('.container-info-selected').css({
      top: `${50 - $(event.currentTarget).scrollTop()}px`
    })
  } else {
    $('.container-info-selected').css({
      top: '0px'
    })
  }
})

$(() => {
  // Busqueda en Tablas
  $(document).on('click', '.checks', (event) => {
    const hasCheck = $(event.currentTarget).is(':checked')
    if (hasCheck) {
      $(event.currentTarget).parent().parent().addClass('item-selected')
    } else {
      $(event.currentTarget).parent().parent().removeClass('item-selected')
    }
    let count = 0
    $('.checks').each(() => {
      if ($(event.currentTarget).is(':checked')) {
        count += 1
      }
    })
    if (count > 0) {
      $('.container-info-selected').css('display', 'flex')
    } else {
      $('.container-info-selected').hide()
    }
    $('.container-info-selected .data').find('.count').text(count)
  })

  $(document).on('click', '.remove-items', (event) => {
    const listId = []
    $('.checks').each(() => {
      if ($(event.currentTarget).is(':checked')) {
        const id = $(event.currentTarget).attr('data-id')
        listId.push(id)
      }
    })
    $('#_mode').val('remove')
    $('#_list').val(listId)

    $('#indexlist').DataTable().clear().destroy()
    $('#indexlist').DataTable(getConfigDataTable())
  })

  $('select')
    .not('[data-path], .convert_options_to_json')
    .each((index, element) => {
      const id = `#${$(element).attr('id')}`
      $(id).change((event) => {
        $(event.currentTarget).children().removeAttr('selected')
        const optionSelected = $(event.currentTarget).val()
        $(id)
          .children()
          .each((indexAlt, elementAlt) => {
            if ($(elementAlt).val() === optionSelected) {
              $(elementAlt).attr('selected', 'selected')
            }
          })
      })
    })
})
