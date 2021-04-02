const html = document.documentElement
const btnToggle = document.querySelector('.header__toggle')
const userEvents = ['click', 'touchstart']

userEvents.forEach(userEvent => {
    btnToggle.addEventListener(userEvent, toggleMenu)
})
function toggleMenu(e) {
    e.preventDefault()
    html.classList.toggle('header--active')
}

const inputPagamento = document.querySelector('.formaPagamento')
const parcelas = document.querySelector('.parcelas')

if((inputPagamento !== null || inputPagamento !== undefined) && (parcelas !== null || parcelas !== undefined)){
    inputPagamento.addEventListener('change', formaPagamento)
    function formaPagamento() {
        if(this.value == 'CREDITO') {
            parcelas.style.display = 'block'
       
        }else {
            parcelas.style.display = null
        }         
         
        }    
    }



