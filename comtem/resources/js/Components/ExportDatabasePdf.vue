<template>
    <button
        @click="exportPDF"
        :disabled="exporting"
        class="export-pdf-btn"
        :class="{ 'exporting': exporting }"
    >
        <span v-if="!exporting">📄 Export All Tables to PDF</span>
        <span v-else>⏳ Generating PDF...</span>
    </button>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ExportDatabasePdf',
    data() {
        return {
            exporting: false,
        };
    },
    methods: {
        async exportPDF() {
            this.exporting = true;
            try {
                const response = await axios.get('/admin/export-all-pdf', {
                    responseType: 'blob',
                });
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `database_export_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.pdf`);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
                this.showNotification?.('PDF exported successfully!', 'success');
            } catch (error) {
                console.error('PDF export failed:', error);
                this.showNotification?.('Failed to export PDF', 'error');
            } finally {
                this.exporting = false;
            }
        },
        showNotification(message, type) {
            if (this.$emit) {
                this.$emit('notify', { message, type });
            } else {
                alert(message);
            }
        },
    },
};
</script>

<style scoped>
.export-pdf-btn {
    background: #e63946;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: auto;
}
.export-pdf-btn:hover:not(:disabled) {
    background: #c62828;
    transform: translateY(-2px);
}
.export-pdf-btn:disabled {
    background: #aaa;
    cursor: not-allowed;
    transform: none;
}
.export-pdf-btn.exporting {
    opacity: 0.7;
}
</style>
