<script>
    window.watsonAssistantChatOptions = {
        integrationID: "211f1752-1384-4277-85d7-44245bb091e8", // The ID of this integration.
        region: "eu-de", // The region your integration is hosted in.
        serviceInstanceID: "077f59cd-5237-4861-9a62-8ae42145420d", // The ID of your service instance.
        onLoad: async (instance) => { await instance.render(); }
    };
    setTimeout(function(){
        const t=document.createElement('script');
        t.src="https://web-chat.global.assistant.watson.appdomain.cloud/versions/" + (window.watsonAssistantChatOptions.clientVersion || 'latest') + "/WatsonAssistantChatEntry.js";
        document.head.appendChild(t);
    });
</script>