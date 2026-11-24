import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import 'flowbite';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
window.ClassicEditor = ClassicEditor;

Alpine.plugin(collapse);
Alpine.start();

// Render Lucide icons
createIcons({ icons });

// Re-render setelah DOM berubah (navigasi / partial reload)
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});