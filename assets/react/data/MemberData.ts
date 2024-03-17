import { ChatData } from "./ChatData"
import { UserData } from "./UserData"

export interface MemberData {
    id: number,
    role: number
    user: UserData | null,
    chat: ChatData | null
}