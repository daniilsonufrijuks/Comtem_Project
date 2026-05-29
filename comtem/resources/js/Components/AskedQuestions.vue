
<template>
    <div id="app">
        <div class="faq-container">
            <div class="faq-header">
                <h1>{{ t('faq_title') }}</h1>
                <p>{{ t('faq_subtitle') }}</p>
            </div>

            <div class="faq-controls">
                <div class="search-box">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <input
                        type="text"
                        v-model="searchQuery"
                        :placeholder="t('faq_search')"
                        @input="onSearchInput"
                    >
                </div>

                <select class="category-filter" v-model="selectedCategory" @change="onFilterChange">
                    <option value="">{{ t('faq_all_categories') }}</option>
                    <option v-for="category in categories" :key="category" :value="category">
                        {{ t('faq_category_' + category.toLowerCase()) }}
                    </option>
                </select>
            </div>

            <div class="faq-list">
                <div
                    v-for="faq in filteredFaqs"
                    :key="faq.id"
                    class="faq-item"
                    :class="{ active: activeFaq === faq.id }"
                >
                    <div class="faq-question" @click="toggleFaq(faq.id)">
                        <div>
                            <h3>{{ faq.question }}</h3>
                            <span class="category-tag">{{ t('faq_category_' + faq.categoryKey) }}</span>
                        </div>
                        <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                    </div>

                    <div class="faq-answer">
                        <div class="faq-answer-content" v-html="faq.answer"></div>
                    </div>
                </div>

                <div v-if="filteredFaqs.length === 0" class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>{{ t('faq_no_results') }}</h3>
                    <p>{{ t('faq_no_results_hint') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useTranslation } from '@/Composables/useTranslation';

export default {
    name: 'FaqComponent',
    setup() {
        const { t } = useTranslation();

        // FAQ keys
        const faqKeys = [
            { id: 1, questionKey: 'faq_q1', answerKey: 'faq_a1', categoryKey: 'account' },
            { id: 2, questionKey: 'faq_q2', answerKey: 'faq_a2', categoryKey: 'billing' },
            { id: 3, questionKey: 'faq_q3', answerKey: 'faq_a3', categoryKey: 'account' },
            { id: 4, questionKey: 'faq_q4', answerKey: 'faq_a4', categoryKey: 'billing' },
            { id: 5, questionKey: 'faq_q5', answerKey: 'faq_a5', categoryKey: 'support' },
            { id: 6, questionKey: 'faq_q6', answerKey: 'faq_a6', categoryKey: 'security' },
        ];

        const activeFaq = ref(faqKeys.length > 0 ? faqKeys[0].id : null);
        const searchQuery = ref('');
        const selectedCategory = ref('');

        // Get unique categories from the keys
        const categories = computed(() => {
            return [...new Set(faqKeys.map(f => f.categoryKey))];
        });

        // Build translated FAQ objects
        const translatedFaqs = computed(() => {
            return faqKeys.map(faq => ({
                id: faq.id,
                question: t(faq.questionKey),
                answer: t(faq.answerKey),
                categoryKey: faq.categoryKey,
            }));
        });

        // Filter translated FAQs
        const filteredFaqs = computed(() => {
            const query = searchQuery.value.toLowerCase().trim();
            const cat = selectedCategory.value.toLowerCase();

            const filtered = translatedFaqs.value.filter(faq => {
                const matchesSearch = query === '' ||
                    faq.question.toLowerCase().includes(query) ||
                    faq.answer.toLowerCase().includes(query);
                const matchesCategory = cat === '' || faq.categoryKey === cat;
                return matchesSearch && matchesCategory;
            });

            // Keep activeFaq valid if possible
            if (filtered.length > 0 && !filtered.some(f => f.id === activeFaq.value)) {
                activeFaq.value = filtered[0].id;
            } else if (filtered.length === 0) {
                activeFaq.value = null;
            }

            return filtered;
        });

        // Methods
        const toggleFaq = (id) => {
            activeFaq.value = activeFaq.value === id ? null : id;
        };

        const onSearchInput = () => {};
        const onFilterChange = () => {};

        return {
            t,
            activeFaq,
            searchQuery,
            selectedCategory,
            categories,
            filteredFaqs,
            toggleFaq,
            onSearchInput,
            onFilterChange,
        };
    },
};
</script>

<style>

.faq-container {
    max-width: 900px;
    width: 100%;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    justify-content: center;
    justify-self: center;
}

.faq-header {
    background: #8258d3;
    color: white;
    padding: 25px 30px;
    text-align: center;
}

.faq-header h1 {
    font-size: 28px;
    margin-bottom: 10px;
}

.faq-header p {
    opacity: 0.9;
}

.faq-controls {
    padding: 20px;
    background: #f8f9fa;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    border-bottom: 1px solid #eaeaea;
}

.search-box {
    flex: 1;
    min-width: 250px;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 40px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #777;
}

.category-filter {
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: white;
    font-size: 16px;
    min-width: 180px;
}

.faq-list {
    padding: 10px;
    max-height: 500px;
    overflow-y: auto;
}

.faq-item {
    margin: 10px;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.faq-question {
    padding: 18px 20px;
    background: #f9f9f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: background 0.3s;
}

.faq-question:hover {
    background: #f1f5fe;
}

.faq-question h3 {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
}

.faq-answer {
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: all 0.4s ease;
    background: white;
    line-height: 1.6;
}

.faq-answer-content {
    padding: 20px;
}

.faq-item.active .faq-answer {
    max-height: 500px;
}

.faq-item.active .faq-question {
    background: #e8f0fe;
    border-bottom: 1px solid #eaeaea;
}

.arrow {
    font-size: 18px;
    transition: transform 0.3s ease;
}

.faq-item.active .arrow {
    transform: rotate(180deg);
    color: #4a6fc7;
}

.category-tag {
    display: inline-block;
    background: #e8f0fe;
    color: #4a6fc7;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-top: 8px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #777;
}

.empty-state i {
    font-size: 50px;
    margin-bottom: 15px;
    color: #ccc;
}

@media (max-width: 600px) {
    .faq-controls {
        flex-direction: column;
    }

    .search-box {
        min-width: 100%;
    }

    .faq-question h3 {
        font-size: 16px;
        padding-right: 15px;
    }
}
</style>
