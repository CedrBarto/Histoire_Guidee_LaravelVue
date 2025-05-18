import { ref, computed } from 'vue';

export function useInventory() {
    const inventory = ref([]);
    const itemsData = ref({});
    const itemObtained = ref(false);
    const itemObtainedName = ref('');

    const items = computed(() => {
        return inventory.value.map(id => itemsData.value[id] || { id, name: `Item #${id}`, description: 'Chargement...' });
    });

    function resetInventory() {
        inventory.value = [];
        itemsData.value = {};
        itemObtained.value = false;
        itemObtainedName.value = '';
        // Supprimer la sauvegarde du localStorage
        localStorage.removeItem('story_progress');
    }

    function loadInventory() {
        const savedProgress = localStorage.getItem('story_progress');
        if (savedProgress) {
            try {
                const progress = JSON.parse(savedProgress);
                inventory.value = progress.inventory || [];
                itemsData.value = progress.itemsData || {};
                loadMissingItemsData();
            } catch (e) {
                // Ne garde que les logs vraiment critiques (ex: catch d'erreur critique), sinon tout supprimer
            }
        }
    }

    async function loadMissingItemsData() {
        const missingItems = inventory.value.filter(id => !itemsData.value[id]);
        if (missingItems.length > 0) {
            for (const id of missingItems) {
                await fetchItemDetails(id);
            }
        }
    }

    async function addToInventory(itemId, storyId, sceneId) {
        itemId = parseInt(itemId);
        
        if (!isNaN(itemId) && !inventory.value.includes(itemId)) {
            inventory.value.push(itemId);
            
            // Charger les données de l'item
            const itemData = await fetchItemDetails(itemId);
            if (itemData) {
                itemObtainedName.value = itemData.name;
                itemObtained.value = true;
            }
            
            // Mettre à jour la sauvegarde
            saveProgress(storyId, sceneId);
            return true;
        }
        
        return false;
    }

    function removeFromInventory(itemId, storyId, sceneId) {
        itemId = parseInt(itemId);
        const index = inventory.value.indexOf(itemId);
        if (index !== -1) {
            inventory.value.splice(index, 1);
            saveProgress(storyId, sceneId);
            return true;
        }
        return false;
    }

    function hasItem(itemId) {
        return inventory.value.includes(parseInt(itemId));
    }

    async function fetchItemDetails(itemId) {
        try {
            const response = await fetch(`/api/items/${itemId}`);
            const data = await response.json();
            if (data.item) {
                itemsData.value[itemId] = data.item;
                return data.item;
            }
            return null;
        } catch (error) {
            // Ne garde que les logs vraiment critiques (ex: catch d'erreur critique), sinon tout supprimer
            return null;
        }
    }

    function saveProgress(storyId, sceneId) {
        const progress = {
            storyId,
            sceneId,
            timestamp: Date.now(),
            inventory: inventory.value,
            itemsData: itemsData.value
        };
        localStorage.setItem('story_progress', JSON.stringify(progress));
    }

    return {
        inventory,
        items,
        itemObtained,
        itemObtainedName,
        loadInventory,
        resetInventory,
        addToInventory,
        removeFromInventory,
        hasItem,
        fetchItemDetails,
        saveProgress
    };
}