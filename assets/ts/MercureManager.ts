export class MercureManager {
    static mercureFetch(action: string, topicName: string, callback: (ev: MessageEvent) => any, error: string) {
        //
        fetch(action).then(response => {

            let link = response.headers.get("Link");
            let result = link?.match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/);
            if (link && result) {
                const url = new URL(result[1]);
                
                //
                url.searchParams.append("topic", topicName);
                const eventSource = new EventSource(url);
                eventSource.onmessage = callback;
            } else {
                console.log({"error": error})
            }
        })
    }
}