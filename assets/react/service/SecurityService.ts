export class SecurityService {
    public static getAuthorizationBearerHeader(): string | null {
        return sessionStorage.getItem("jwt_token") == null ? null : "Bearer " + sessionStorage.getItem("jwt_token");
    }
}