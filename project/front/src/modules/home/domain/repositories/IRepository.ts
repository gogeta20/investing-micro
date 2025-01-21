import type { ResponseBasic, ResponseData } from "@/modules/home/domain/models/ResponseData";

export interface IRepository <T = ResponseData> {
  send<D>(data:D): Promise<T>;
  setEndPoint(path: string): void;
}
