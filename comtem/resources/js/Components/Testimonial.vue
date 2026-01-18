<template>
    <section class="testimonial-section">
        <div class="parent-container">
            <blockquote class="responsive-quote">
                <p class="headline">
                    Dear Valued Customer,
                </p>

                <p>
                    Welcome to <strong>COMTEM</strong> — where cutting-edge technology meets exceptional service.
                    Whether you’re a tech enthusiast, a professional seeking reliable solutions, or simply looking
                    for the latest innovations, we’re here to support you every step of the way.
                </p>

                <p>
                    Our commitment goes beyond products. We focus on quality, reliability, and customer satisfaction
                    to ensure you always get technology you can trust.
                </p>

                <cite>— COMTEM DEV GROUP</cite>
            </blockquote>
        </div>
    </section>
</template>


<style scoped>
.testimonial-section {
    background: linear-gradient(135deg, #3b0a57, #4b1170);
    padding: 80px 0;
    margin-top: 100px;
}

.parent-container {
    width: 90%;
    max-width: 800px;
    margin: 0 auto;
}

.responsive-quote {
    position: relative;
    padding: 45px;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    color: #333;
    line-height: 1.7;
}

/* Decorative quote marks */
.responsive-quote::before,
.responsive-quote::after {
    content: "❝";
    position: absolute;
    font-size: 110px;
    color: #7a3a7b;
    opacity: 0.08;
    font-family: serif;
}

.responsive-quote::before {
    top: -25px;
    left: 20px;
}

.responsive-quote::after {
    content: "❞";
    bottom: -35px;
    right: 20px;
}

/* Text */
.responsive-quote p {
    font-size: 16.5px;
    margin-bottom: 18px;
}

.headline {
    font-size: 24px;
    font-weight: 700;
    color: #7a3a7b;
    margin-bottom: 12px;
}

/* Cite */
.responsive-quote cite {
    display: block;
    margin-top: 22px;
    font-size: 14.5px;
    font-weight: 600;
    color: #7a3a7b;
    text-align: right;
}

/* Mobile */
@media (max-width: 600px) {
    .responsive-quote {
        padding: 28px;
    }

    .headline {
        font-size: 21px;
    }

    .responsive-quote p {
        font-size: 15px;
    }
}

</style>
