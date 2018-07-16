import { formatDate } from './timeHelpers.js'

export default class Expense {
  constructor(id,e_id,date,name,val,comment,img){
    this.id=id
    this.e_id=e_id
    this.date=new Date(date)
    this.name=name
    this.value=val
    this.comment=comment
    this.image=img
  }

  render(){
    let t=''
    let date=formatDate(this.date)
    let t1=`
    <tr>
      <td>${this.id}</td>
      <td>${date}</td>
      <td>${this.name}</td>
      <td class="hidden-xs hidden-sm">${this.comment}</td>
      <td class="text-right">
      `
/*
<a class="btn btn-default" href="assets/images/droplets.jpg" data-featherlight="image">Image</a>

<button type="button" class="btn btn-default btn-circle">
  <i class="fa fa-image"></i>
</button>`


*/

    let t2=  `
      <a class="btn btn-default btn-circle" href="https://filmstunden.ch/upload/${this.image}" data-featherlight="image">
        <i class="fa fa-image"></i>
      </a>
`
    let t3 = `
        <button type="button" class="btn btn-default btn-circle">
            <i class="fa fa-pencil"></i>
         </button>
         <button type="button" class="btn btn-danger btn-circle delExpense" id="${this.e_id}">
            <i class="fa fa-times"></i>
          </button>
        </td>
      </tr>
    `
    if(this.image){
      t=t1+t2+t3
    }else{
      t=t1+t3
    }

    return t
  }
}
