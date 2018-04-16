export default class Company {
  constructor(id){
    this.id=id
    this.name=''
    this.address=[]
    this.mail=''
    this.tel=''
  }

  loadCompany(){
    let p=$.ajax({
      url: 'https://filmstunden.ch/api/v01/company/'+this.id,
      dataType: 'json',
      type: 'GET',
      context: this
    })
    p=p.then((data) => {
      this.name = data.name
      this.address[0] = data.address_1
      this.address[1] = data.address_2
      this.mail=data.mail
      this.tel=data.telephone
    })
    return p
  }

  get address1(){
    return this.address[0]
  }
  get address2(){
    return this.address[1]
  }

}
