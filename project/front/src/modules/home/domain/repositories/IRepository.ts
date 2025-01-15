import type { ResponseData } from "@/modules/home/domain/models/ResponseData";

export interface IRepository <T = ResponseData> {
  send(): Promise<T>;
  setEndPoint(path: string): void;
}
