<template>
    <div class="library-container">

        <h2 class="library-title">
            📘 Computer Documentation
        </h2>

        <div class="books-grid">

            <div v-for="book in books" :key="book.id" class="book-card">

                <div class="book-image">
                    <img :src="book.img" alt="Book cover" />
                </div>

                <div class="book-content">
                    <h3 class="book-name">{{ book.name }}</h3>

                    <p class="book-description">
                        {{ book.description }}
                    </p>

                    <a
                        :href="book.file_path"
                        target="_blank"
                        class="read-button"
                    >
                        📄 Read PDF
                    </a>
                </div>

            </div>

        </div>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'BooksLibrary',
    data() {
        return {
            books: []
        };
    },
    mounted() {
        this.fetchBooks();
    },
    methods: {
        fetchBooks() {
            fetch('/books')
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Fetched products:", data);
                    this.books = data;
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
    },
};
</script>

<style scoped>
.library-container {
    max-width: 1200px;
    margin: 70px auto;
    padding: 0 20px;
}

.library-title {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 40px;
}

/* grid */

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 30px;
}

/* book card */

.book-card {
    background: white;
    border-radius: 14px;
    overflow: hidden;

    display: flex;
    flex-direction: column;

    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.book-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 28px rgba(0,0,0,0.15);
}

/* image */

.book-image {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f6f6;
}

.book-image img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

/* content */

.book-content {
    padding: 18px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.book-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.book-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

/* button */

.read-button {
    margin-top: auto;
    padding: 10px 14px;
    text-align: center;

    background: #2563eb;
    color: white;
    border-radius: 8px;

    font-size: 14px;
    font-weight: 500;

    text-decoration: none;
    transition: background 0.2s ease;
}

.read-button:hover {
    background: #1d4ed8;
}
</style>
