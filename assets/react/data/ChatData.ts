import { FileData } from "./FileData";
import { MessageData } from "./MessageData";

export interface ChatData {
    id: number,
    name: string | null,
    avatar: string | null,
    folder: string| null,
    last_message: MessageData | null,
    messages: MessageData | null,
    files: FileData | null
}