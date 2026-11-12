function BookExCard (data) {
    data.forEach(exgan => {
                    document.getElementById("cardsContaners").innerHTML +=`
            <div class="bg-white h-[500px] rounded p-4">
                <div class="bg-white h-full w-full">
                    <div>
                        <div class="flex justify-between p-4">
                        ${
                            exgan.exchange_type == "temporary"  ?
                            `<div class="font-bold text-lg bg-yellow-200 text-green-yellow rounded p-2"><span><i
                                        class="bi bi-shield-check"></i> echeang temporary</span></div>
                            <div class="font-bold text-lg  p-2"><i class="bi bi-clipboard"></i> il y a 2 joure </div>`
                            :
                            `<div class="font-bold text-lg bg-green-200 text-green-950 rounded p-2"><span><i
                                        class="bi bi-shield-check"></i> echeang definitif</span></div>`
                        }
                        
                            
                            
                        </div>
                        <div class="flex justify-center content-center ">
                            <div class="flex gap-4">
                                <div class="w-[200px]">
                                    <div class="h-[200px] w-[200px]">
                                        <img src="http://127.0.0.1:8000${exgan.book.image_url}"
                                            alt="" class=" w-full h-full object-cover  ">
                                    </div>
                                    <div class="">
                                        <h3>${exgan.book.title}</h3>
                                        <p>${exgan.book.author}</p>
                                        <p>${exgan.book.category.slug}</p>

                                    </div>
                                </div>
                                <div class="flex flex-col justify-center content-center">
                                    <div></div>
                                    <div class="">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <div></div>
                                </div>
                                ${

                                    exgan.offers.find((offer)=> offer.status == "accepted" )
                                    ?
                                    `
                                    <div>
                                    <div class="h-[200px] w-[200px]">
                                        <img src="http://127.0.0.1:8000${exgan.book.image_url}"
                                            alt="" class=" w-full h-full object-cover  ">
                                    </div>
                                    <div class="">
                                        <h3>${exgan.offers.find((offer)=> offer.status == "accepted" ).offered_book.title}</h3>
                                        <p>${exgan.offers.find((offer)=> offer.status == "accepted" ).offered_book.author}</p>
                                        <p>${exgan.offers.find((offer)=> offer.status == "accepted" ).offered_book.category.slug}</p>
                                    </div>
                                    </div>`
                                    :
                                    ""

                                }

                            </div>
                        </div>
                        <div class="flex justify-between p-4 mt-8">
                            <div class="flex">
                                <div class=" h-[80px] w-[80px]" >
                                    <img src="http://127.0.0.1:8000${exgan.owner.avatar}" alt="" class="w-full h-full  object-cover rounded-full">
                                </div>
                                <div class="mx-3">
                                    <p>${exgan.owner.avatar}</p>
                                   <p>${exgan.owner.email}</p>
                                </div>
                            </div>
                            <button class="bg-sky-900 text-white py-4 px-8 my-3 rounded"><i
                                    class="bi bi-arrow-repeat mx-1 "></i>deatals </button>
                        </div>
                    </div>
                </div>

            </div>
    `
        }

    );
    
}





fetch("http://127.0.0.1:8000/api/v1/exchange-requests")
        .then((res)=> res.json())
        .then((data)=> {BookExCard(data.data); console.log(data.data)}  )