<template>
    <section id="productdetails" class="section-p1">
        <div class="single-pro-image">
            <img src="/images/front/inteli5.png" width="50%" id="MainImg" alt="">
            <div class="small-image-group">
                <div class="small-img-col">
                    <img src="/images/front/inteli5.png" width="100%" class="small-img" alt="">
                </div>
                <div class="small-img-col">
                    <img src="/images/front/inteli5.png" width="100%" class="small-img" alt="">
                </div>
                <div class="small-img-col">
                    <img src="/images/front/inteli5.png" width="100%" class="small-img" alt="">
                </div>
                <div class="small-img-col">
                    <img src="/images/front/inteli5.png" width="100%" class="small-img" alt="">
                </div>
            </div>
        </div>
        <div class="single-pro-details">
            <h6>CPU</h6>
            <h4>Intel Core i5 12 GEN</h4>
            <h2>$220.00</h2>
            <select>
                <option>Select option</option>
                <option>Box</option>
                <option>Single</option>
            </select>
            <input type="number" value="1">
            <button class="normal">Add to Cart</button>
            <h4>Product Details</h4>
            <span class="gcardt">Advancing Performance Hybrid Architecture
        Intel® Core™ 14th gen processors advances performance hybrid architecture1 with up to
        eight Performance-cores (P-core) and up to 16 Efficient-cores (E-core),
        combined with workloads intelligently routed by Intel® Thread Director2.</span>
        </div>
    </section>
</template>

<style scoped>
/* product */
#productdetails {
    display: flex;
    margin-top: 110px;
    margin-bottom: 110px;
    justify-content: center;
    align-items: center;
    width: 80%;
    max-width: 1200px;
    padding: 50px;
    box-sizing: border-box;
    /* left: 100px; */
    left: 15%;
    position: relative;
}

.single-pro-image {
    justify-content: center;
    align-items: center;
}

#productdetails .single-pro-image{
    width: 40%;
    margin-right: 50px;
}

.small-image-group {
    display: flex;
    justify-content: space-between;
}

.small-img-col {
    flex-basis: 24%;
    cursor: pointer;
}

#productdetails .single-pro-details {
    width: 50%;
    padding-top: 30px;
}

#productdetails .single-pro-details h4 {
    padding: 40px 0 20px 0;
    font-weight: 700;
}

#productdetails .single-pro-details h2 {
    font-size: 26px;
    font-weight: 700;
}

#productdetails .single-pro-details select {
    display: block;
    padding: 5px 10px;
    margin-bottom: 10px;
}

#productdetails .single-pro-details input {
    width: 50px;
    height: 47px;
    padding-left: 10px;
    font-size: 16px;
    margin-right: 10px;
}

#productdetails .single-pro-details button {
    background-color: #7a3a7b;
    color: #e8e8e8;
    border-radius: 5px;
    padding: 5px 15px;
}

#productdetails .single-pro-details input:focus {
    outline: none;
}

#productdetails .single-pro-details span {
    line-height: 25px;
}

.gcardt {
    animation: animt 5s ease-in-out infinite;
}

@keyframes animt {
    0% {
        color: #9a73a1;
    }

    50% {
        color: #8b2c84;
    }

    100% {
        color: #5c0a55;
    }
}

/* product */


/* product */
@media (max-width: 768px) {
    #productdetails {
        display: flex;
        flex-direction: column;
    }

    #productdetails .single-pro-image {
        width: 100%;
        margin-right: 0px;
    }

    #productdetails .single-pro-details {
        width: 100%;
    }
}

/* product */
</style>
